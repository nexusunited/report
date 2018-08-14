<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace SprykerMiddleware\Zed\Report\Persistence;

use Generated\Shared\Transfer\ProcessResultTransfer;
use Generated\Shared\Transfer\SpyProcessEntityTransfer;
use Spryker\Zed\Kernel\Persistence\AbstractEntityManager;
use Spryker\Zed\Kernel\Persistence\EntityManager\EntityManagerInterface;

/**
 * @method \SprykerMiddleware\Zed\Report\Persistence\ReportPersistenceFactory getFactory()
 */
class ReportEntityManager extends AbstractEntityManager implements ReportEntityManagerInterface, EntityManagerInterface
{
    /**
     * @param \Generated\Shared\Transfer\SpyProcessEntityTransfer $spyProcessEntityTransfer
     *
     * @return \Generated\Shared\Transfer\SpyProcessEntityTransfer
     */
    public function saveProcess(SpyProcessEntityTransfer $spyProcessEntityTransfer): SpyProcessEntityTransfer
    {
        $spyProcess = $this->getFactory()
            ->createProcessQuery()
            ->filterByProcessName($spyProcessEntityTransfer->getProcessName())
            ->findOneOrCreate();

        $spyProcess->save();
        $spyProcessEntityTransfer->setIdProcess($spyProcess->getIdProcess());

        return $spyProcessEntityTransfer;
    }

    /**
     * @param \Generated\Shared\Transfer\ProcessResultTransfer $processResultTransfer
     * @param \Generated\Shared\Transfer\SpyProcessEntityTransfer $spyProcessEntityTransfer
     *
     * @return \Generated\Shared\Transfer\ProcessResultTransfer
     */
    public function saveProcessResult(
        ProcessResultTransfer $processResultTransfer,
        SpyProcessEntityTransfer $spyProcessEntityTransfer
    ): ProcessResultTransfer {

        $entityTransfer = $this->getFactory()
            ->createProcessResultMapper()
            ->mapProcessResultTransferToEntityTransfer($processResultTransfer);

        $entityTransfer->setFkProcessId($spyProcessEntityTransfer->getIdProcess());

        $entityTransfer = $this->save($entityTransfer);

        return $this->getFactory()
            ->createProcessResultMapper()
            ->mapEntityTransferToProcessResultTransfer($entityTransfer);
    }
}